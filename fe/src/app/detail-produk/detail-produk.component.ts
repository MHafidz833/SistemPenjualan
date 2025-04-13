import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ProductService } from './product.service';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';

@Component({
  selector: 'app-product-detail',
  templateUrl: './detail-produk.component.html',
  styleUrls: ['./detail-produk.component.css'],
  standalone: true,
  imports: [CommonModule, FormsModule, NavbarComponent, FooterComponent]
})
export class DetailProdukComponent implements OnInit {
  product: any;
  quantity: number = 1;
  remainingStock: number = 0;

  constructor(
    private route: ActivatedRoute,
    private productService: ProductService
  ) { }

  ngOnInit(): void {
    const productId = this.route.snapshot.paramMap.get('id');
    if (productId) {
      this.productService.getProductById(+productId).subscribe(response => {
        if (response.status === 'success') {
          this.product = response.data;
          this.remainingStock = this.product.stok - this.quantity; // Hitung sisa stok saat pertama kali dimuat
        }
      });
    }
  }

  increaseQuantity(): void {
    if (this.product && this.quantity < this.product.stok) {
      this.quantity++;
      this.updateRemainingStock();
    }
  }

  decreaseQuantity(): void {
    if (this.quantity > 1) {
      this.quantity--;
      this.updateRemainingStock();
    }
  }

  updateRemainingStock(): void {
    this.remainingStock = this.product.stok - this.quantity;
  }

  addToCart(): void {
    const cart = JSON.parse(localStorage.getItem('cart') || '{}');
    const productId = this.product.id_produk;

    if (cart[productId]) {
      cart[productId] += this.quantity;
    } else {
      cart[productId] = this.quantity;
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    alert('Produk berhasil ditambahkan ke keranjang!');
  }

  buyNow(): void {
    this.addToCart();
    window.location.href = '/cart';
  }
}
