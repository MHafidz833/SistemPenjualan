import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { SidebarComponent } from '../left-sidebar/left-sidebar.component';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

@Component({
  selector: 'admin-app-pembayaran',
  standalone: true,
  imports: [SidebarComponent, CommonModule, ReactiveFormsModule, FormsModule],
  templateUrl: './edit-order.component.html',
  styleUrl: './edit-order.component.css'
})
export class AdminEditOrderComponent implements OnInit {
  id_order: string | null = null;
  paymentDetails: any = {};
  statusList = ['Menyiapkan Produk', 'Produk Dikirim'];
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
          this.status = this.paymentDetails.status || '';
          this.resi = this.paymentDetails.resi || '';
        } else {
          alert('Data pembayaran tidak ditemukan!');
        }
      },
      (error) => {
        console.error('Error fetching payment details:', error);
        alert('Gagal mengambil data pembayaran.');
      }
    );
  }

  updateOrderStatus(): void {
    if (!this.status) {
      alert('Harap pilih status pesanan.');
      return;
    }

    if (this.status === 'Produk Dikirim' && !this.resi.trim()) {
      alert('Harap isi nomor resi sebelum mengirim produk.');
      return;
    }

    const requestData = {
      id_order: this.id_order,
      status: this.status,
      resi: this.status === 'Produk Dikirim' ? this.resi : '' // Kosongkan resi jika bukan "Produk Dikirim"
    };

    this.http.post<any>(this.apiUrl, requestData).subscribe(
      (response) => {
        if (response.status === 'success') {
          alert('Status pesanan berhasil diperbarui!');
          window.location.href = '/admin/order'; // Redirect ke halaman order list
        } else {
          alert('Gagal memperbarui status pesanan.');
        }
      },
      (error) => {
        console.error('Error updating order status:', error);
        alert('Terjadi kesalahan saat memperbarui status.');
      }
    );
  }
}
