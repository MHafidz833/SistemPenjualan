import { ComponentFixture, TestBed } from '@angular/core/testing';
import { AdminDetailOrderComponent } from './detail-order.component';
import { ActivatedRoute } from '@angular/router';
import { of } from 'rxjs';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { OrderService } from './order.service';
import { By } from '@angular/platform-browser';

describe('AdminDetailOrderComponent', () => {
  let component: AdminDetailOrderComponent;
  let fixture: ComponentFixture<AdminDetailOrderComponent>;
  let orderService: OrderService;

  const mockOrder = {
    pelanggan: {
      nm_pelanggan: 'John Doe',
      email: 'john@example.com'
    },
    alamat_penerima: {
      nm_penerima: 'Jane Doe',
      alamat_pengiriman: 'Jl. Contoh No.1',
      kode_pos: '12345'
    },
    catatan: '',
    tanggal_order: '2024-01-01',
    status: {
      status: 'Dikirim',
      badge: {
        badge: 'info',
        residue: 'Barang sedang dikirim'
      }
    },
    products: [
      {
        gambar: 'produk.jpg',
        nm_produk: 'Hijab Segi Empat',
        harga: 50000,
        jml_order: 2,
        subharga: 100000
      }
    ],
    subtotal: 100000,
    shipping: 15000,
    total_order: 115000
  };

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminDetailOrderComponent, HttpClientTestingModule],
      providers: [
        {
          provide: ActivatedRoute,
          useValue: {
            params: of({ id: '123' }),
            queryParams: of({}),
            snapshot: {
              paramMap: {
                get: (key: string) => '123'
              }
            }
          }
        }
      ]
    }).compileComponents();

    fixture = TestBed.createComponent(AdminDetailOrderComponent);
    component = fixture.componentInstance;
    orderService = TestBed.inject(OrderService);

    spyOn(orderService, 'getOrderDetail').and.returnValue(of(mockOrder));
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should load order details correctly', () => {
    expect(component.order).toEqual(mockOrder);
    expect(component.loading).toBeFalse();
  });

  it('should render customer and shipping address correctly', () => {
    const compiled = fixture.nativeElement;
    expect(compiled.textContent).toContain('John Doe');
    expect(compiled.textContent).toContain('john@example.com');
    expect(compiled.textContent).toContain('Jane Doe');
    expect(compiled.textContent).toContain('Jl. Contoh No.1');
    expect(compiled.textContent).toContain('12345');
  });

  it('should render order status and date', () => {
    const compiled = fixture.nativeElement;
    expect(compiled.textContent).toContain('Dikirim');
    expect(compiled.textContent).toContain('Barang sedang dikirim');
    expect(compiled.textContent).toContain('2024-01-01');
  });

  it('should render product details correctly', () => {
    const rows = fixture.debugElement.queryAll(By.css('table tbody tr'));
    const productRow = rows[0].nativeElement;

    expect(productRow.textContent).toContain('Hijab Segi Empat');
    expect(productRow.textContent).toContain('Rp. 50,000');
    expect(productRow.textContent).toContain('2');
    expect(productRow.textContent).toContain('Rp. 100,000');

    const subtotalRow = rows[1].nativeElement;
    const shippingRow = rows[2].nativeElement;
    const totalRow = rows[3].nativeElement;

    expect(subtotalRow.textContent).toContain('Rp. 100,000');
    expect(shippingRow.textContent).toContain('Rp. 15,000');
    expect(totalRow.textContent).toContain('Rp. 115,000');
  });

  it('should show loading template before data is loaded', () => {
    component.loading = true;
    fixture.detectChanges();

    const loadingText = fixture.nativeElement.querySelector('p');
    expect(loadingText).toBeTruthy();
    expect(loadingText.textContent).toContain('Loading data...');
  });

  it('should return correct status class', () => {
    expect(component.getStatusClass('info')).toBe('badge-info');
    expect(component.getStatusClass('warning')).toBe('badge-warning');
    expect(component.getStatusClass('secondary')).toBe('badge-secondary');
    expect(component.getStatusClass('danger')).toBe('badge-danger');
    expect(component.getStatusClass('success')).toBe('badge-success');
    expect(component.getStatusClass('other')).toBe('badge-light');
  });
});
