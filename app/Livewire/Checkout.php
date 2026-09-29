<?php

namespace App\Livewire;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use RuntimeException;

#[Layout('components.layouts.app')]
#[Title('Checkout')]
class Checkout extends Component
{
    public string $customer_name = '';
    public string $customer_email = '';
    public string $customer_phone = '';
    public string $address = '';
    public string $province = '';
    public string $city = '';
    public string $district = '';
    public string $postal_code = '';
    public string $note = '';
    public string $gift_note = '';

    public ?string $error = null;

    public function mount(): void
    {
        if (empty(session('cart', []))) {
            $this->redirectRoute('cart', navigate: true);

            return;
        }

        if ($user = auth()->user()) {
            $this->customer_name = (string) $user->name;
            $this->customer_email = (string) $user->email;
        }
    }

    protected function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'address' => ['required', 'string', 'max:1000'],
            'province' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:10'],
            'note' => ['nullable', 'string', 'max:500'],
            'gift_note' => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format email belum sesuai.',
            'max' => ':attribute terlalu panjang.',
            'regex' => 'Format nomor belum sesuai.',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'customer_name' => 'Nama',
            'customer_email' => 'Email',
            'customer_phone' => 'Nomor WhatsApp',
            'address' => 'Alamat',
            'province' => 'Provinsi',
            'city' => 'Kota',
            'district' => 'Kecamatan',
            'postal_code' => 'Kode pos',
        ];
    }

    public function placeOrder(): void
    {
        $this->validate();
        $this->error = null;

        $cart = session('cart', []);

        if (empty($cart)) {
            $this->redirectRoute('cart', navigate: true);

            return;
        }

        try {
            $order = DB::transaction(function () use ($cart) {
                // Cegah dua pesanan mendapat nomor urut yang sama (khusus PostgreSQL)
                DB::select('select pg_advisory_xact_lock(?)', [7301]);

                /** @var Collection<int, ProductVariant> $variants */
                $variants = ProductVariant::with('product.project')
                    ->whereIn('id', array_keys($cart))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                /** @var array<int, array{0: ProductVariant, 1: Product, 2: int}> $items */
                $items = [];
                $subtotal = 0;

                foreach ($cart as $id => $qty) {
                    /** @var ProductVariant|null $variant */
                    $variant = $variants->get($id);
                    /** @var Product|null $product */
                    $product = $variant?->product;

                    if (! $variant || ! $product || $product->status !== 'active') {
                        throw new RuntimeException('Ada produk di keranjang yang sudah tidak tersedia.');
                    }

                    if (! ($product->project?->is_open_for_sale ?? true)) {
                        throw new RuntimeException("Penjualan {$product->name} belum atau sudah tidak dibuka.");
                    }

                    if ($qty > max(0, $variant->stock - $variant->reserved)) {
                        throw new RuntimeException("Stok {$product->name} tidak mencukupi. Silakan ubah jumlah di keranjang.");
                    }

                    $items[] = [$variant, $product, (int) $qty];
                    $subtotal += $variant->price * $qty;
                }

                $prefix = 'ES-' . now()->format('Ymd') . '-';
                $sequence = Order::where('code', 'like', $prefix . '%')->count() + 1;

                /** @var Order $order */
                $order = Order::create([
                    ...$this->only([
                        'customer_name', 'customer_email', 'customer_phone',
                        'address', 'province', 'city', 'district', 'postal_code',
                    ]),
                    'code' => $prefix . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT),
                    'user_id' => auth()->id(),
                    'subtotal' => $subtotal,
                    'shipping_cost' => 0,
                    'discount' => 0,
                    'total' => $subtotal,
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'note' => $this->note ?: null,
                    'gift_note' => $this->gift_note ?: null,
                    'expires_at' => now()->addHours(24),
                ]);

                foreach ($items as [$variant, $product, $qty]) {
                    $order->items()->create([
                        'product_variant_id' => $variant->id,
                        'product_name' => $product->name,
                        'sku' => $variant->sku,
                        'variant_label' => trim(implode(' / ', array_filter([$variant->size, $variant->color]))) ?: null,
                        'price' => $variant->price,
                        'quantity' => $qty,
                    ]);

                    // Stok dikunci saat pembayaran dimulai (FR-303)
                    $variant->increment('reserved', $qty);
                }

                return $order;
            });
        } catch (RuntimeException $e) {
            $this->error = $e->getMessage();

            return;
        }

        // Email konfirmasi (FR-901). Kegagalan email tidak boleh membatalkan pesanan.
        try {
            Mail::to($order->customer_email)->send(new OrderConfirmation($order));
        } catch (\Throwable $e) {
            report($e);
        }

        session()->forget('cart');
        $this->dispatch('cart-updated');

        $this->redirect(
            URL::signedRoute('order.show', ['order' => $order->code]),
            navigate: true
        );
    }

    public function render()
    {
        $cart = session('cart', []);

        /** @var Collection<int, ProductVariant> $variants */
        $variants = ProductVariant::with('product')
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $lines = [];
        $subtotal = 0;

        foreach ($cart as $id => $qty) {
            $variant = $variants->get($id);

            if (! $variant || ! $variant->product) {
                continue;
            }

            $total = $variant->price * $qty;

            $lines[] = [
                'name' => $variant->product->name,
                'label' => trim(implode(' / ', array_filter([$variant->size, $variant->color]))),
                'qty' => $qty,
                'total' => $total,
            ];

            $subtotal += $total;
        }

        return view('livewire.checkout', compact('lines', 'subtotal'));
    }
}