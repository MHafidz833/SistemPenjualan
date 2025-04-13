import { ComponentFixture, TestBed } from '@angular/core/testing';
import { AdminKategoriPosComponent } from './kategori-pos.component';
import { HttpClientTestingModule } from '@angular/common/http/testing'; // <-- Tambahkan ini

describe('AdminKategoriPosComponent', () => {
  let component: AdminKategoriPosComponent;
  let fixture: ComponentFixture<AdminKategoriPosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        AdminKategoriPosComponent,
        HttpClientTestingModule // <-- Tambahkan ini juga
      ]
    }).compileComponents();

    fixture = TestBed.createComponent(AdminKategoriPosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
