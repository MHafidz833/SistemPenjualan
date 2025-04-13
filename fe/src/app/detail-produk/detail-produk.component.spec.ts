import { ComponentFixture, TestBed } from '@angular/core/testing';
import { DetailProdukComponent } from './detail-produk.component';
import { RouterTestingModule } from '@angular/router/testing'; // Menambahkan RouterTestingModule
import { HttpClientTestingModule } from '@angular/common/http/testing'; // Menambahkan HttpClientTestingModule
import { ProductService } from './product.service';

describe('DetailProdukComponent', () => {
  let component: DetailProdukComponent;
  let fixture: ComponentFixture<DetailProdukComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        DetailProdukComponent,
        HttpClientTestingModule, // Menambahkan HttpClientTestingModule
        RouterTestingModule // Menambahkan RouterTestingModule untuk ActivatedRoute
      ],
      providers: [ProductService] // Menambahkan ProductService sebagai provider
    })
    .compileComponents();

    fixture = TestBed.createComponent(DetailProdukComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
