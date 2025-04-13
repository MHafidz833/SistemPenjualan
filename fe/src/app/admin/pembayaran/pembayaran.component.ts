import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { SidebarComponent } from '../left-sidebar/left-sidebar.component';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

@Component({
  selector: 'admin-app-pembayaran',
  imports: [SidebarComponent, CommonModule, ReactiveFormsModule, FormsModule],
  templateUrl: './pembayaran.component.html',
  styleUrl: './pembayaran.component.css'
})
export class AdminPembayaranComponent implements OnInit {
  id_order: string | null = null;
  paymentDetails: any = {};
  statusList = ['Belum Dibayar', 'Sudah Dibayar', 'Menyiapkan Produk', 'Produk Dikirim'];

  status: string = '';
  resi: string = '';
  apiUrl = 'http://localhost/mahiahijab/api/admin/order/pembayaran.php';

  constructor(private http: HttpClient, private route: ActivatedRoute) { }

  ngOnInit(): void {
    this.id_order = this.route.snapshot.paramMap.get('id');
    if (this.id_order) {
      this.getPaymentDetails(this.id_order);
    }
  }

  getPaymentDetails(id_order: string): void {
    this.http.get<any>(`${this.apiUrl}?id=${id_order}`).subscribe(
      (response) => {
        if (response.status === 'success') {
          this.paymentDetails = response.payment_details;
        } else {
          alert('Data pembayaran tidak ditemukan!');
        }
      },
      (error) => {
        console.error('Error fetching payment details:', error);
        // alert('Gagal mengambil data pembayaran.');
      }
    );
  }

  updateOrderStatus(): void {
    if (!this.status || !this.resi) {
      alert('Harap isi status dan nomor resi!');
      return;
    }

    const requestData = {
      id_order: this.id_order,
      status: this.status,
      resi: this.resi
    };

    this.http.post<any>(this.apiUrl, requestData).subscribe(
      (response) => {
        if (response.status === 'success') {
          alert('Status pesanan berhasil diperbarui!');
          window.location.href = '/orders'; // Redirect ke halaman order list
        } else {
          alert('Gagal memperbarui status order.');
        }
      },
      (error) => {
        console.error('Error updating order status:', error);
        alert('Terjadi kesalahan saat memperbarui status.');
      }
    );
  }
}
