import { ComponentFixture, TestBed } from '@angular/core/testing';
import { KonfirmasiPembayaranComponent } from './konfirmasi-pembayaran.component';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';
import { of } from 'rxjs';

describe('KonfirmasiPembayaranComponent', () => {
  let component: KonfirmasiPembayaranComponent;
  let fixture: ComponentFixture<KonfirmasiPembayaranComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        KonfirmasiPembayaranComponent, // Komponen utama
        HttpClientTestingModule,
        NavbarComponent,               // ✅ Import sebagai standalone
        FooterComponent                // ✅ Import sebagai standalone
      ],
      providers: [
        {
          provide: ActivatedRoute,
          useValue: {
            snapshot: {
              paramMap: {
                get: (key: string) => '123'
              }
            },
            params: of({ order_id: '123' })
          }
        },
        {
          provide: Router,
          useValue: {
            navigate: jasmine.createSpy('navigate')
          }
        }
      ]
    }).compileComponents();

    fixture = TestBed.createComponent(KonfirmasiPembayaranComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
