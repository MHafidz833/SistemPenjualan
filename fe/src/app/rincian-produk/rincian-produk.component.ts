import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ProductService } from './product.service';
import { CommonModule } from '@angular/common';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';

@Component({
  selector: 'app-rincian-produk',
  imports: [CommonModule, NavbarComponent, FooterComponent],
  templateUrl: './rincian-produk.component.html',
  styleUrl: './rincian-produk.component.css'
})
export class RincianProdukComponent implements OnInit {
  productList: any[] = [];
  orderId: number | null = null;
  isLoading: boolean = true;
  errorMessage: string = '';

  constructor(private route: ActivatedRoute, private productService: ProductService) { }

  ngOnInit(): void {
    this.route.paramMap.subscribe(params => {
      this.orderId = Number(params.get('id'));
      if (this.orderId) {
        this.fetchProductDetails(this.orderId);
      }
    });
  }

  fetchProductDetails(id: number): void {
    this.productService.getProductDetails(id).subscribe({
      next: (response) => {
        if (response.status === 'success') {
          this.productList = response.data;
        } else {
          this.errorMessage = 'Data tidak ditemukan.';
        }
        this.isLoading = false;
      },
      error: (error) => {
        this.errorMessage = 'Gagal mengambil data produk.';
        console.error(error);
        this.isLoading = false;
      }
    });
  }
}
