// checkout.component.ts
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { RajaOngkirService } from '../service/rajaongkir.service';
import { OrderService } from './order.service';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';
import { ProductService } from '../service/product.service';

@Component({
  selector: 'app-checkout',
  templateUrl: './checkout.component.html',
  styleUrls: ['./checkout.component.css'],
  imports: [CommonModule, NavbarComponent, FooterComponent, ReactiveFormsModule]
})
export class CheckoutComponent implements OnInit {
  checkoutForm: FormGroup;
  provinces: any[] = [];
  cities: any[] = [];
  cartItems: any[] = [];
  totalHarga: number = 0;
  subtotal: number = 0;
  ongkir: number = 0;
  id_pelanggan: number = 0;

  constructor(
    private fb: FormBuilder,
    private rajaOngkirService: RajaOngkirService,
    private orderService: OrderService,
    private productService: ProductService,
    private router: Router
  ) {
    this.checkoutForm = this.fb.group({
      nama: ['', Validators.required],
      no_telp: ['', [Validators.required, Validators.pattern('^[0-9]+$')]],
      province_destination: ['', Validators.required],
      city_destination: ['', Validators.required],
      kodePos: ['', Validators.required],
      alamat: ['', Validators.required],
      catatan: [''],
      ongkir: [0, Validators.required],
      subtotal: [0, Validators.required],
      total_order: [0, Validators.required]
    });
  }

  ngOnInit(): void {
    this.loadProvinces();
    this.loadCart();
  }

  loadProvinces() {
    this.rajaOngkirService.getProvinces().subscribe(response => {
      this.provinces = response.rajaongkir.results;
    });
  }

  getCities(provinceId: string) {
    this.rajaOngkirService.getCities(provinceId).subscribe(response => {
      this.cities = response.rajaongkir.results;
    });
  }

  setPostalCodeAndOngkir(cityId: string) {
    const selectedCity = this.cities.find(city => city.city_id === cityId);
    if (selectedCity) {
      this.checkoutForm.patchValue({ kodePos: selectedCity.postal_code });
      this.calculateOngkir(cityId);
    }
  }

  calculateSubtotal() {
    this.subtotal = this.cartItems.reduce((total, item) => total + (item.harga * item.quantity), 0);
    this.calculateTotal();
  }

  calculateOngkir(destinationCityId: string) {
    const origin = '171';
    const weight = this.cartItems.reduce((total, item) => total + (item.quantity * 200), 0);
    const courier = 'pos';

    if (!destinationCityId || weight <= 0) return;

    this.rajaOngkirService.calculateOngkir(origin, destinationCityId, weight, courier).subscribe(response => {
      if (response.rajaongkir?.results?.[0]?.costs?.[0]?.cost?.[0]?.value) {
        this.ongkir = response.rajaongkir.results[0].costs[0].cost[0].value;
        this.checkoutForm.patchValue({ ongkir: this.ongkir });
        this.calculateTotal();
      }
    });
  }

  calculateTotal() {
    const totalOrder = this.subtotal + this.ongkir;
    this.totalHarga = totalOrder;
    this.checkoutForm.patchValue({ total_order: totalOrder });
  }

  loadCart() {
    const storedCart = localStorage.getItem('cart');
    if (!storedCart) {
      this.cartItems = [];
      this.calculateSubtotal();
      return;
    }

    const cartData = JSON.parse(storedCart);
    this.cartItems = [];
    this.subtotal = 0;

    Object.keys(cartData).forEach(id_produk => {
      this.productService.getProductById(id_produk).subscribe(response => {
        if (response.status === 'success') {
          const product = response.data;
          const quantity = cartData[id_produk];
          const itemSubtotal = parseFloat(product.harga) * quantity;

          this.cartItems.push({
            id_produk: product.id_produk,
            nm_produk: product.nm_produk,
            harga: parseFloat(product.harga),
            quantity: quantity,
            subtotal: itemSubtotal
          });

          this.calculateSubtotal();
        }
      });
    });
  }

  placeOrder() {
    if (this.checkoutForm.invalid) {
      alert('Harap isi semua data dengan benar.');
      return;
    }

    const userData = localStorage.getItem('user');
    if (userData) {
      const user = JSON.parse(userData);
      this.id_pelanggan = user.id_pelanggan;
    }

    const orderData = {
      id_pelanggan: this.id_pelanggan,
      no_telp: this.checkoutForm.value.no_telp,
      province_destination: this.checkoutForm.value.province_destination,
      city_destination: this.checkoutForm.value.city_destination,
      kodePos: this.checkoutForm.value.kodePos,
      alamat: this.checkoutForm.value.alamat,
      catatan: this.checkoutForm.value.catatan,
      ongkir: this.ongkir,
      subtotal: this.subtotal,
      total_order: this.totalHarga,
      items: this.cartItems.map(item => ({
        id_produk: item.id_produk,
        jumlah: item.quantity
      }))
    };

    console.log('Order data:', orderData);

    this.orderService.placeOrder(orderData).subscribe(response => {
      console.log('Response:', response);
      alert('Pesanan berhasil dibuat!');
      localStorage.removeItem('cart');
      this.router.navigate(['/orderan'], { queryParams: { order_id: response.order_id } });
    }, error => {
      console.error('Error:', error);
      alert('Terjadi kesalahan saat melakukan pemesanan.');
    });
  }
}
