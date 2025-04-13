import { ComponentFixture, TestBed } from '@angular/core/testing';
import { BlogComponent } from './blog.component';
import { HttpClientTestingModule } from '@angular/common/http/testing'; // for HttpClient
import { RouterTestingModule } from '@angular/router/testing'; // if needed for routing

describe('BlogComponent', () => {
  let component: BlogComponent;
  let fixture: ComponentFixture<BlogComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        BlogComponent,
        HttpClientTestingModule,  // Add HttpClientTestingModule
        RouterTestingModule       // Add RouterTestingModule if BlogComponent uses Router
      ]
    }).compileComponents();

    fixture = TestBed.createComponent(BlogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
