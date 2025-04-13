import { ComponentFixture, TestBed } from '@angular/core/testing';
import { DetailBlogComponent } from './detail-blog.component';
import { HttpClientTestingModule } from '@angular/common/http/testing'; // Import HttpClientTestingModule
import { ActivatedRoute } from '@angular/router';
import { of } from 'rxjs';  // Mocking ActivatedRoute
import { ArticleService } from '../service/article.service'; // Pastikan service di-import

describe('DetailBlogComponent', () => {
  let component: DetailBlogComponent;
  let fixture: ComponentFixture<DetailBlogComponent>;
  let articleService: ArticleService;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        DetailBlogComponent,
        HttpClientTestingModule,  // Tambahkan HttpClientTestingModule
      ],
      providers: [
        ArticleService,  // Pastikan ArticleService terdaftar di providers
        { 
          provide: ActivatedRoute, 
          useValue: { 
            snapshot: { 
              paramMap: { 
                get: jasmine.createSpy().and.returnValue('1')  // Mock paramMap.get() untuk mengembalikan '1'
              } 
            }
          } 
        }
      ]
    }).compileComponents();

    fixture = TestBed.createComponent(DetailBlogComponent);
    component = fixture.componentInstance;
    articleService = TestBed.inject(ArticleService); // Ambil instance ArticleService
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should get article by id from the service', () => {
    // Mocking response from articleService.getArticleById()
    const mockArticle = { status: 'success', data: { title: 'Test Article', content: 'Article content here.' } };
    spyOn(articleService, 'getArticleById').and.returnValue(of(mockArticle)); // Simulate API call

    component.ngOnInit(); // Trigger ngOnInit

    expect(articleService.getArticleById).toHaveBeenCalledWith('1'); // Check if the service was called with the correct ID
    expect(component.article.title).toBe('Test Article'); // Verify the article data is set correctly
  });
});
