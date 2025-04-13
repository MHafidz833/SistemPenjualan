import { ComponentFixture, TestBed } from '@angular/core/testing';
import { AdminUbahProdukComponent } from './ubah-produk.component';
import { RouterTestingModule } from '@angular/router/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { ReactiveFormsModule } from '@angular/forms';  // Pastikan ReactiveFormsModule diimpor
import { ActivatedRoute } from '@angular/router';
import { of } from 'rxjs';

// Stub untuk ActivatedRoute
class ActivatedRouteStub {
  snapshot = {
    paramMap: {
      get: (key: string) => {
        if (key === 'id') return '1'; // Simulasi id produk
        return null;
      }
    }
  };
}

describe('AdminUbahProdukComponent', () => {
  let component: AdminUbahProdukComponent;
  let fixture: ComponentFixture<AdminUbahProdukComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        AdminUbahProdukComponent,
        HttpClientTestingModule,
        ReactiveFormsModule,  // Pastikan ReactiveFormsModule diimpor
        RouterTestingModule
      ],
      providers: [
        { provide: ActivatedRoute, useClass: ActivatedRouteStub }
      ]
    }).compileComponents();

    fixture = TestBed.createComponent(AdminUbahProdukComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
