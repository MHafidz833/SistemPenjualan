import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { OrderService } from './order.service';
import { CommonModule } from '@angular/common';
import { SidebarComponent } from '../left-sidebar/left-sidebar.component';

@Component({
  selector: 'admin-app-detail-order',
  imports: [CommonModule, SidebarComponent],
  templateUrl: './detail-order.component.html',
  styleUrl: './detail-order.component.css'
})
export class AdminDetailOrderComponent implements OnInit {
  order: any;
  orderId!: number;
  loading: boolean = true;

  constructor(
    private route: ActivatedRoute,
    private orderService: OrderService
  ) { }

  ngOnInit(): void {
    this.orderId = Number(this.route.snapshot.paramMap.get('id'));
    this.fetchOrderDetail();
  }

  fetchOrderDetail(): void {
    this.orderService.getOrderDetail(this.orderId).subscribe(
      (data) => {
        this.order = data;
        this.loading = false;
      },
      (error) => {
        console.error('Error fetching order details', error);
        this.loading = false;
      }
    );
  }

  getStatusClass(status: string): string {
    switch (status) {
      case 'warning': return 'badge-warning';
      case 'secondary': return 'badge-secondary';
      case 'info': return 'badge-info';
      case 'danger': return 'badge-danger';
      case 'success': return 'badge-success';
      default: return 'badge-light';
    }
  }

}
