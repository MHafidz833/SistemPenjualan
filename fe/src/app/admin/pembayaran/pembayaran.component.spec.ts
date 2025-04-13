import { ComponentFixture, TestBed } from '@angular/core/testing';
import { AdminPembayaranComponent } from './pembayaran.component'; // Gunakan AdminPembayaranComponent
import { provideRouter } from '@angular/router';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { ActivatedRoute } from '@angular/router';
import { of } from 'rxjs';

// Simulasi ActivatedRoute dengan parameter dummy
const dummyActivatedRoute = {
  snapshot: {
    paramMap: {
      get: (key: string) => '123'
    }
  },
  paramMap: of({
    get: (key: string) => '123'
  })
};

describe('AdminPembayaranComponent', () => {
  let component: AdminPembayaranComponent;
  let fixture: ComponentFixture<AdminPembayaranComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        HttpClientTestingModule,
        AdminPembayaranComponent // Pindahkan ke imports, bukan declarations
      ],
      providers: [
        { provide: ActivatedRoute, useValue: dummyActivatedRoute },
        provideRouter([]) // Masukkan jika ada routing dependencies
      ]
    }).compileComponents();

    fixture = TestBed.createComponent(AdminPembayaranComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
