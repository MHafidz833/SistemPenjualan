import { ComponentFixture, TestBed } from '@angular/core/testing';
import { RincianProdukComponent } from './rincian-produk.component';
import { RouterTestingModule } from '@angular/router/testing'; // Menambahkan RouterTestingModule untuk ActivatedRoute
import { HttpClientTestingModule } from '@angular/common/http/testing'; // Menambahkan HttpClientTestingModule
import { ProductService } from './product.service';

describe('RincianProdukComponent', () => {
  let component: RincianProdukComponent;
  let fixture: ComponentFixture<RincianProdukComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        RincianProdukComponent,
        RouterTestingModule, // Menambahkan RouterTestingModule untuk ActivatedRoute
        HttpClientTestingModule // Menambahkan HttpClientTestingModule untuk HTTP
      ],
      providers: [ProductService] // Menambahkan ProductService sebagai provider
    })
    .compileComponents();

    fixture = TestBed.createComponent(RincianProdukComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
