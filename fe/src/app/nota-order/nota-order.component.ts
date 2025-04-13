import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { OrderService } from './order.service';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';
import { HttpClient } from '@angular/common/http';
import { CommonModule } from '@angular/common';


@Component({
  selector: 'app-notaorder',
  templateUrl: './nota-order.component.html',
  styleUrls: ['./nota-order.component.css'],
  imports: [NavbarComponent, FooterComponent, CommonModule],
  standalone: true,
})
export class NotaOrderComponent implements OnInit {
  order: any;
  orderDetails: any;
  totalWeight: number = 0;
  subtotal: number = 0;
  isLoading: boolean = true;

  constructor(
    private orderService: OrderService,
    private route: ActivatedRoute
  ) { }

  ngOnInit(): void {
    const orderId = +this.route.snapshot.paramMap.get('id')!;
    this.orderService.getOrderDetails(orderId).subscribe(
      (response) => {
        this.isLoading = false;
        if (response.status === 'success') {
          this.order = response.order;
          this.orderDetails = response.details;

          // Calculate total weight
          this.totalWeight = this.orderDetails.reduce(
            (acc: number, item: any) => acc + item.subharga,
            0
          );

          // Calculate subtotal
          this.subtotal = this.orderDetails.reduce(
            (acc: number, item: any) => acc + item.subharga,
            0
          );
        }
      },
      (error) => {
        this.isLoading = false;
        console.error('Error fetching order details', error);
      }
    );
  }

  printOrder() {
    window.print();
  }
}
