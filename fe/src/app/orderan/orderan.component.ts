import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-orderan',
  imports: [NavbarComponent, FooterComponent, CommonModule],
  templateUrl: './orderan.component.html',
  styleUrl: './orderan.component.css'
})
export class OrderanComponent implements OnInit {
  orders: any[] = [];
  id_pelanggan: string | null = null;

  constructor(private http: HttpClient, private router: Router) { }

  ngOnInit(): void {
    this.loadUserData();
    if (this.id_pelanggan) {
      this.fetchOrders();
    } else {
      alert('Silakan login terlebih dahulu.');
      this.router.navigate(['/login']);
    }
  }

  loadUserData(): void {
    const userData = localStorage.getItem('user');
    if (userData) {
      const user = JSON.parse(userData);
      this.id_pelanggan = user.id_pelanggan;
    }
  }

  fetchOrders(): void {
    const apiUrl = `http://localhost/mahiahijab/api/order/orderan.php?id_pelanggan=${this.id_pelanggan}`;
    this.http.get<any>(apiUrl).subscribe(response => {
      // alert(response.data);
      if (response.status == 'success') {
        this.orders = response.data;
      } else {
        alert(response.message);
      }
    });
  }

  getBadgeClass(status: string): string {
    switch (status) {
      case 'Belum Dibayar': return 'badge badge-warning';
      case 'Sudah Dibayar': return 'badge badge-secondary';
      case 'Menyiapkan Produk': return 'badge badge-info';
      case 'Produk Dikirim': return 'badge badge-danger';
      case 'Produk Diterima': return 'badge badge-success';
      default: return 'badge badge-light';
    }
  }

  konfirmasiDiterima(orderId: number): void {
    if (confirm('Apakah Anda yakin ingin mengkonfirmasi bahwa pesanan telah diterima?')) {
      const apiUrl = `http://localhost/mahiahijab/api/order/konfirmasi-barang.php?id=${orderId}`;
      this.http.get<any>(apiUrl).subscribe(response => {
        if (response.status === 'success') {
          alert('Pesanan berhasil dikonfirmasi!');
          this.fetchOrders(); // Refresh data
        } else {
          alert(response.message);
        }
      });
    }
  }
}
