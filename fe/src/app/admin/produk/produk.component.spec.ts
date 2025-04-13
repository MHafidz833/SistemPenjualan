import { ComponentFixture, TestBed } from '@angular/core/testing';
import { AdminProdukComponent } from './produk.component';
import { HttpClientTestingModule } from '@angular/common/http/testing'; // Import untuk HttpClientTestingModule
import { CommonModule } from '@angular/common'; // Menambahkan CommonModule
import { RouterTestingModule } from '@angular/router/testing'; // Jika menggunakan routerLink

describe('AdminProdukComponent', () => {
  let component: AdminProdukComponent;
  let fixture: ComponentFixture<AdminProdukComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        AdminProdukComponent,           // Menambahkan komponen standalone ke dalam imports
        HttpClientTestingModule,        // Menambahkan HttpClientTestingModule untuk HttpClient
        CommonModule,                   // Menambahkan CommonModule jika komponen menggunakan fitur seperti *ngIf atau *ngFor
        RouterTestingModule             // Menambahkan RouterTestingModule jika menggunakan routing
      ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(AdminProdukComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
