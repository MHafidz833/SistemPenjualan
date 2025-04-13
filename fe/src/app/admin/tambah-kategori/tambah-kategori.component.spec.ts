import { ComponentFixture, TestBed } from '@angular/core/testing';
import { HttpClientModule } from '@angular/common/http'; // ✅ Tambahkan HttpClientModule
import { AdminTambahKategoriComponent } from './tambah-kategori.component';
import { CategoryService } from './category.service'; // Pastikan service ini juga tersedia untuk tes

describe('TambahKategoriComponent', () => {
  let component: AdminTambahKategoriComponent;
  let fixture: ComponentFixture<AdminTambahKategoriComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        AdminTambahKategoriComponent, // Impor komponen yang digunakan
        HttpClientModule, // Impor HttpClientModule
      ],
      providers: [CategoryService] // Sediakan CategoryService untuk test
    })
    .compileComponents();

    fixture = TestBed.createComponent(AdminTambahKategoriComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
