import { ComponentFixture, TestBed } from '@angular/core/testing';
import { HttpClientModule } from '@angular/common/http'; // ⬅ Tambahkan ini
import { AdminTambahPosComponent } from './tambah-pos.component';

describe('TambahPosComponent', () => {
  let component: AdminTambahPosComponent;
  let fixture: ComponentFixture<AdminTambahPosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminTambahPosComponent, HttpClientModule] // ⬅ Tambahkan HttpClientModule di sini
    })
    .compileComponents();

    fixture = TestBed.createComponent(AdminTambahPosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
