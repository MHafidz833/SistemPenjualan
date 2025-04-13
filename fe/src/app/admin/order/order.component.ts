import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { RouterModule, Router } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { DecimalPipe, DatePipe } from '@angular/common'; // Pipe tambahan
import { SidebarComponent } from '../left-sidebar/left-sidebar.component';

@Component({
    selector: 'app-order',
    templateUrl: './order.component.html',
    styleUrls: ['./order.component.css'],
    standalone: true,  // Standalone component
    imports: [
        CommonModule,
        RouterModule,
        FormsModule,
        SidebarComponent
    ],
    providers: [DecimalPipe, DatePipe] // Tambahkan penyedia pipe jika masih error
})
export class OrderComponent implements OnInit {
    totalOrder: number = 0;
    blmDibayar: number = 0;
    sudahDibayar: number = 0;
    menyiapkanProduk: number = 0;
    produkDikirim: number = 0;
    diterima: number = 0;
    orders: any[] = [];

    private apiUrl = 'http://localhost/mahiahijab/api/admin/order/order.php';

    constructor(private http: HttpClient, public router: Router) { }

    ngOnInit(): void {
        this.fetchOrderStats();
        this.fetchOrders();
        this.checkLogin();
    }


    fetchOrderStats(): void {
        this.http.get<any>(`${this.apiUrl}?action=getStats`, { withCredentials: true })
            .subscribe(
                data => {
                    this.totalOrder = data.totalOrder || 0;
                    this.blmDibayar = data.blmDibayar || 0;
                    this.sudahDibayar = data.sudahDibayar || 0;
                    this.menyiapkanProduk = data.menyiapkanProduk || 0;
                    this.produkDikirim = data.produkDikirim || 0;
                    this.diterima = data.diterima || 0;
                },
                error => console.error('Gagal mengambil statistik order', error)
            );
    }

    fetchOrders(): void {
        this.http.get<any[]>(`${this.apiUrl}?action=getOrders`, { withCredentials: true })
            .subscribe(
                data => {
                    this.orders = data;
                },
                error => console.error('Gagal mengambil data order', error)
            );
    }

    goToOrderDetail(id: number): void {
        this.router.navigate([`/admin/order/`, id]).then(() => {
            console.log('Navigasi ke halaman detail order');
            window.location.reload();
        });
    }

    goToPaymentDetail(id: number): void {
        this.router.navigate([`/admin/edit-order/${id}`]).then(() => {
            console.log('Navigasi ke halaman detail order');
            window.location.reload();
        });
    }

    goToEditOrder(id: number): void {
        this.router.navigate([`/admin/edit-order/${id}`]).then(() => {
            console.log('Navigasi ke halaman detail order');
            window.location.reload();
        });
    }

    handleAction(order: any): void {
        if (order.status === 'Sudah Dibayar') {
            alert('Fungsi lihat pembayaran masih dalam pengembangan.');
        } else if (order.status === 'Menyiapkan Produk') {
            alert('Fungsi edit orderan masih dalam pengembangan.');
        }
    }
    
    checkLogin(): void {
        this.http.get<any>('http://localhost/mahiahijab/api/admin/auth/login.php', { withCredentials: true })
            .subscribe({
                next: (response) => {
                    if (response.status != 'success') {
                        this.redirectToLogin();
                    }
                },
                error: (err) => {
                    console.error('Gagal memeriksa sesi', err);
                    this.redirectToLogin();
                }
            });
    }

    redirectToLogin(): void {
        alert('Silakan login terlebih dahulu!');
        this.router.navigate(['/admin/login']);
    }
}
