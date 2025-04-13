import { Component, OnInit } from '@angular/core';
import { ProductService } from './product.service';
import Swal from 'sweetalert2';
import { CommonModule } from '@angular/common';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';


@Component({
  selector: 'app-cart',
  imports: [CommonModule, NavbarComponent, FooterComponent],
  templateUrl: './cart.component.html',
  styleUrl: './cart.component.css',
})
export class CartComponent implements OnInit {
  cartItems: any[] = [];
  subtotal: number = 0;

  constructor(private productService: ProductService) { }

  ngOnInit(): void {
    this.loadCart();
  }

  // Memuat produk yang ada di keranjang belanja
  loadCart() {
    const storedCart = localStorage.getItem('cart');
    if (!storedCart) {
      this.cartItems = [];
      this.calculateSubtotal();
      return;
    }

    const cartData = JSON.parse(storedCart); // Format { 'id_produk': jumlah }
    this.cartItems = [];

    Object.keys(cartData).forEach(id_produk => {
      this.productService.getProductById(id_produk).subscribe(response => {
        if (response.status === 'success') {
          const product = response.data;
          this.cartItems.push({
            id_produk: product.id_produk,
            nm_produk: product.nm_produk,
            gambar: product.gambar,
            harga: parseFloat(product.harga),
            quantity: cartData[id_produk],
            subtotal: parseFloat(product.harga) * cartData[id_produk]
          });
          this.calculateSubtotal();
        }
      });
    });
  }


  // Menghitung subtotal
  calculateSubtotal() {
    this.subtotal = this.cartItems.reduce((total, item) => total + (item.harga * item.jumlah), 0);
  }

  // Hapus item dari keranjang belanja
  removeFromCart(id_produk: string) {
    Swal.fire({
      title: "Apakah Kamu Yakin?",
      text: "Produk Akan Di Hapus Dari Daftar Keranjang",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Hapus Sekarang",
      cancelButtonText: "Batal"
    }).then((result) => {
      if (result.isConfirmed) {
        this.cartItems = this.cartItems.filter(item => item.id_produk !== id_produk);
        localStorage.setItem('cart', JSON.stringify(this.cartItems));
        this.calculateSubtotal();
        Swal.fire("Dihapus!", "Produk Berhasil Dihapus Dari Keranjang", "success");
      }
    });
  }

  // Navigasi ke halaman checkout
  checkout() {
    if (this.cartItems.length === 0) {
      Swal.fire({
        title: 'Keranjang Kosong',
        text: 'Silahkan Memilih Produk Dahulu!',
        icon: 'warning',
        confirmButtonText: 'OK'
      });
      return;
    }

    const userLoggedIn = localStorage.getItem('user');
    if (!userLoggedIn) {
      Swal.fire({
        title: "Anda Belum Login",
        text: "Silahkan Melakukan Login terlebih Dahulu!",
        icon: "info",
        confirmButtonText: "Login Sekarang"
      }).then(() => {
        window.location.href = '/login';
      });
    } else {
      window.location.href = '/checkout';
    }
  }
}
