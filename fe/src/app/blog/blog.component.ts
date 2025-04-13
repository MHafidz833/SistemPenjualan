import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';

@Component({
  selector: 'app-blog',
  templateUrl: './blog.component.html',
  styleUrls: ['./blog.component.css'],
  standalone: true,
  imports: [CommonModule, NavbarComponent, FooterComponent],
})
export class BlogComponent implements OnInit {
  articles: any[] = [];
  filteredArticles: any[] = [];
  categories: string[] = [];
  
  constructor(private http: HttpClient) {}

  ngOnInit(): void {
    this.fetchData();
  }

  fetchData(): void {
    this.http.get<any>('http://localhost/mahiahijab/api/article/getArticles.php')
      .subscribe(response => {
        this.categories = response.categories.map((cat: any) => cat.nm_kategori);
        this.articles = response.articles;
        this.filteredArticles = [...this.articles]; // Awalnya tampil semua
      });
  }

  filterByCategory(event: any): void {
    const selectedCategory = event.target.value;

    if (selectedCategory) {
      this.filteredArticles = this.articles.filter(article => article.nm_kategori === selectedCategory);
    } else {
      this.filteredArticles = [...this.articles]; // Semua artikel
    }
  }
}
