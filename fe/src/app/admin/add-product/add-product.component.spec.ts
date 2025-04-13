import { ComponentFixture, TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing'; // Menambahkan HttpClientTestingModule
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { RouterTestingModule } from '@angular/router/testing'; // Menambahkan RouterTestingModule untuk pengujian navigasi
import { AdminAddProductComponent } from './add-product.component';


describe('AdminAddProductComponent', () => {
  let component: AdminAddProductComponent;
  let fixture: ComponentFixture<AdminAddProductComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        AdminAddProductComponent, // Mengimpor komponen yang diuji
        HttpClientTestingModule,  // Menambahkan HttpClientTestingModule untuk menggantikan HttpClient dalam pengujian
        ReactiveFormsModule,      // Menambahkan ReactiveFormsModule
        FormsModule,              // Menambahkan FormsModule
        RouterTestingModule       // Menambahkan RouterTestingModule untuk pengujian router
      ],
    })
    .compileComponents();

    fixture = TestBed.createComponent(AdminAddProductComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
