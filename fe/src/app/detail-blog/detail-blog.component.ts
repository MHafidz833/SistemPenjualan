import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ArticleService } from '../service/article.service';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-detail-blog',
  templateUrl: './detail-blog.component.html',
  styleUrls: ['./detail-blog.component.css'],
  imports: [NavbarComponent, FooterComponent, CommonModule]
})
export class DetailBlogComponent implements OnInit {
  article: any = {};

  constructor(
    private route: ActivatedRoute,
    private articleService: ArticleService
  ) { }

  ngOnInit(): void {
    const id = this.route.snapshot.paramMap.get('id'); // Ambil ID dari URL
    if (id) {
      this.articleService.getArticleById(id).subscribe(response => {
        if (response.status === 'success') {
          this.article = response.data;
        }
      });
    }
  }
}
