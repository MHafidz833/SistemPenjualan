import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { SidebarComponent } from '../left-sidebar/left-sidebar.component';
import { Router } from '@angular/router';

@Component({
  selector: 'admin-app-pelanggan',
  imports: [CommonModule, SidebarComponent],
  templateUrl: './pelanggan.component.html',
  styleUrl: './pelanggan.component.css'
})
export class AdminPelangganComponent implements OnInit {
  pelangganList: any[] = [];
  apiUrl = 'http://localhost/mahiahijab/api/admin/pelanggan/pelanggan.php';
  authCheckUrl = 'http://localhost/mahiahijab/api/admin/auth/login.php'; // API untuk validasi login

  constructor(private http: HttpClient, private router: Router) {}

  ngOnInit(): void {
    this.checkLogin(); // Cek login sebelum mengambil data pelanggan
  }

  checkLogin(): void {
    this.http.get<any>(this.authCheckUrl, { withCredentials: true }).subscribe(
      (response) => {
        console.log('Login check response:', response); // Debugging
        if (response.status === 'success' && response.session_id) {
          this.getPelanggan(); // Jika sesi valid, ambil data pelanggan
        } else {
          this.redirectToLogin();
        }
      },
      (error) => {
        console.error('Gagal memeriksa sesi:', error);
        this.redirectToLogin();
      }
    );
  }

  redirectToLogin(): void {
    alert('Silakan login terlebih dahulu!');
    this.router.navigate(['/admin/login']);
  }

  getPelanggan(): void {
    this.http.get<any>(this.apiUrl, { withCredentials: true }).subscribe(
      (response) => {
        console.log('Response pelanggan:', response); // Debugging
        if (response.status === 'error') {
          this.redirectToLogin();
        } else {
          this.pelangganList = response.data || [];
        }
      },
      (error) => {
        console.error('Error fetching data pelanggan:', error);
      }
    );
  }

  deletePelanggan(id: string): void {
    if (confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')) {
      const body = new FormData();
      body.append('id', id);
  
      this.http.post<any>(this.apiUrl, body, { withCredentials: true }).subscribe(
        (response) => {
          if (response.status === 'success') {
            alert('Pelanggan berhasil dihapus.');
            this.getPelanggan(); // Refresh daftar pelanggan setelah hapus
          } else {
            alert('Gagal menghapus pelanggan: ' + response.message);
          }
        },
        (error) => {
          console.error('❌ Error saat menghapus pelanggan:', error);
        }
      );
    }
  }
  
  
}
