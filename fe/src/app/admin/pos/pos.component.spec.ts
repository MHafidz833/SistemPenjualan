import { ComponentFixture, TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing'; // Menambahkan HttpClientTestingModule
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { PosComponent } from './pos.component'; // Komponen Pos
import { PostService } from './post.service';
import { RouterTestingModule } from '@angular/router/testing'; // Modul untuk routing dalam pengujian
import { SidebarComponent } from '../left-sidebar/left-sidebar.component'; // Jika digunakan di dalam PosComponent

describe('PosComponent', () => {
  let component: PosComponent;
  let fixture: ComponentFixture<PosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        PosComponent, // Komponen Pos
        HttpClientTestingModule,  // Menambahkan HttpClientTestingModule untuk mengatasi HttpClient
        RouterTestingModule, // Modul untuk routing dalam pengujian
        ReactiveFormsModule,
        FormsModule
      ],
      providers: [PostService] // Provider untuk PostService
    })
    .compileComponents();

    fixture = TestBed.createComponent(PosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
