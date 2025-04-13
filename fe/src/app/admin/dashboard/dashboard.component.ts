import { Component, OnInit, AfterViewInit } from '@angular/core';
import Chart from 'chart.js/auto';
import { HttpClient } from '@angular/common/http';
import { Router, RouterModule } from '@angular/router';
import { SidebarComponent } from '../left-sidebar/left-sidebar.component';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';



@Component({
    selector: 'app-dashboard',
    standalone: true,
    imports: [CommonModule, FormsModule, SidebarComponent,RouterModule],
    templateUrl: './dashboard.component.html',
    styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit, AfterViewInit {
    totalPendapatan = 0;
    totalMember = 0;
    totalProduk = 0;
    totalArtikel = 0;
    kategoriLabels: string[] = [];
    stokData: number[] = [];
    chartInstance: any;

    constructor(private http: HttpClient, private router: Router) {}

    ngOnInit(): void {
        this.checkLogin();
        this.fetchDashboardData();
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

    ngAfterViewInit(): void {
        setTimeout(() => this.renderChart(), 500);
    }

    fetchDashboardData(): void {
        this.http.get<any>('http://localhost/mahiahijab/api/admin/dashboard/dashboard.php', { withCredentials: true })
            .subscribe({
                next: (data) => {
                    this.totalPendapatan = data.total_pendapatan || 0;
                    this.totalMember = data.total_member || 0;
                    this.totalProduk = data.total_produk || 0;
                    this.totalArtikel = data.total_artikel || 0;

                    this.kategoriLabels = data.stok_produk?.map((item: any) => item.kategori) || [];
                    this.stokData = data.stok_produk?.map((item: any) => item.stok) || [];

                    if (this.kategoriLabels.length && this.stokData.length) {
                        this.renderChart();
                    }
                },
                error: (err) => {
                    console.error('Gagal mengambil data dashboard', err);
                    alert('Gagal mengambil data dashboard. Silakan coba lagi.');
                }
            });
    }

    renderChart(): void {
        setTimeout(() => {
            const canvas = document.getElementById('myChart') as HTMLCanvasElement;
            if (!canvas) {
                console.error("Canvas tidak ditemukan!");
                return;
            }

            const ctx = canvas.getContext('2d');
            if (!ctx) {
                console.error("Context tidak ditemukan!");
                return;
            }

            if (this.chartInstance) {
                this.chartInstance.destroy();
            }

            this.chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: this.kategoriLabels,
                    datasets: [{
                        label: 'Stok Produk',
                        data: this.stokData,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }, 500);
    }
}
