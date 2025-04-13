import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';

@Component({
  selector: 'app-index',
  templateUrl: './index.component.html',
  imports: [NavbarComponent, FooterComponent, FormsModule, CommonModule],
  styleUrls: ['./index.component.css'],
  standalone: true,
})
export class IndexComponent implements OnInit {
  
  
  products: any[] = [];
  categories: any[] = [];

  constructor(private http: HttpClient, public router: Router) { }

  ngOnInit(): void {
    this.fetchData();
  }

  fetchData(category: string = '', search: string = ''): void {
    let url = 'http://localhost/mahiahijab/api/product/product.php';
    if (category) {
      url += `?kategori=${category}`;
    } else if (search) {
      url += `?select=${search}`;
    }
    
    this.http.get<any>(url).subscribe(data => {
      this.products = data.products;
      this.categories = data.categories;
    });
  }


  onSelectCategory(category: string): void {
    this.fetchData(category);
  }
}