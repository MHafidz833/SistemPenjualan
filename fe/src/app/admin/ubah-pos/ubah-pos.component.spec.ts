import { ComponentFixture, TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing'; // Import module ini
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { AdminUbahPosComponent } from './ubah-pos.component'; // Komponen Standalone
import { PosService } from './pos.service';
import { RouterTestingModule } from '@angular/router/testing'; // Import RouterTestingModule untuk router

describe('UbahPosComponent', () => {
  let component: AdminUbahPosComponent;
  let fixture: ComponentFixture<AdminUbahPosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        AdminUbahPosComponent,  // Menggunakan AdminUbahPosComponent di imports (bukan declarations)
        HttpClientTestingModule,  // Menambahkan HttpClientTestingModule
        ReactiveFormsModule,
        FormsModule,
        RouterTestingModule // Pastikan Router juga disediakan jika digunakan
      ],
      providers: [PosService] // Provider untuk layanan
    })
    .compileComponents();

    fixture = TestBed.createComponent(AdminUbahPosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
