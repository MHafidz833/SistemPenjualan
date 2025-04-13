import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { SidebarComponent } from '../left-sidebar/left-sidebar.component';
import { Router } from '@angular/router';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'admin-app-produk',
  imports: [CommonModule, SidebarComponent, RouterModule],
  templateUrl: './produk.component.html',
  styleUrl: './produk.component.css',
  standalone: true
})
export class AdminProdukComponent implements OnInit {
  productList: any[] = [];
  apiUrl = 'http://localhost/mahiahijab/api/admin/product/Product.php';
  isLoggedIn: boolean = false; // Tambahkan indikator login

  constructor(public http: HttpClient, public router: Router) { }

  ngOnInit(): void {
    this.checkLogin(); // Panggil checkLogin terlebih dahulu
  }

  // Validasi status login
  checkLogin(): void {
    this.http.get<any>('http://localhost/mahiahijab/api/admin/auth/login.php', { withCredentials: true })
      .subscribe({
        next: (response) => {
          if (response.status === 'success') {
            this.isLoggedIn = true; // Tandai pengguna telah login
            this.getProducts(); // Panggil getProducts jika login berhasil
          } else {
            this.redirectToLogin();
          }
        },
        error: (err) => {
          console.error('Gagal memeriksa sesi', err);
          this.redirectToLogin();
        }
      });
  }

  // Redirect jika tidak login
  redirectToLogin(): void {
    alert('Silakan login terlebih dahulu!');
    this.router.navigate(['/admin/login']);
  }

  // Mengambil daftar produk dari API
  getProducts(): void {
    if (!this.isLoggedIn) {
      console.log('Pengguna belum login, data tidak dimuat.');
      return; // Cegah pemuatan data jika belum login
    }

    this.http.get<any>(this.apiUrl).subscribe(
      (response) => {
        console.log('Response dari API:', response); // Debugging
        if (response.products) {
          this.productList = response.products;
        } else {
          console.error('Data tidak ditemukan atau API gagal.');
        }
      },
      (error) => {
        console.error('Error fetching data:', error);
      }
    );
  }

  // Menghapus produk dengan metode DELETE
  deleteProduct(id: string): void {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
      const deleteUrl = `${this.apiUrl}?id=${id}`; // Kirim ID dalam parameter URL
      console.log('Menghapus produk dengan ID:', id); // Debugging

      this.http.delete<any>(deleteUrl).subscribe(
        (response) => {
          console.log('Response dari API Hapus:', response); // Debugging
          if (response.status === 'success') {
            alert('Produk berhasil dihapus');
            this.getProducts(); // Refresh daftar produk setelah penghapusan
          } else {
            alert('Gagal menghapus produk.');
          }
        },
        (error) => {
          console.error('Error deleting product:', error);
        }
      );
    }
  }

  onAddProduct(): void {
    this.router.navigate(['/admin/add-product']).then(() => {
      console.log('Navigasi ke halaman pos');
      window.location.reload();
    });
  }

  onCategory(): void {
    this.router.navigate(['/admin/category']).then(() => {
      console.log('Navigasi ke halaman pos');
      window.location.reload();
    });
  }

  onEdit(): void {
    this.router.navigate(['/admin/edit-product']).then(() => {
      console.log('Navigasi ke halaman pos');
      window.location.reload();
    });
  }
}
