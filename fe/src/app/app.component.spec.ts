import { TestBed, ComponentFixture } from '@angular/core/testing';
import { AppComponent } from './app.component';

describe('AppComponent', () => {
  let fixture: ComponentFixture<AppComponent>;  // Menentukan tipe untuk fixture
  let app: AppComponent;  // Menentukan tipe eksplisit untuk app

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AppComponent],  // Mengimpor AppComponent
    }).compileComponents();

    fixture = TestBed.createComponent(AppComponent);
    app = fixture.componentInstance;  // Menetapkan app dengan tipe AppComponent
    fixture.detectChanges();  // Memastikan rendering komponen terjadi
  });

  it('should create the app', () => {
    expect(app).toBeTruthy();  // Memastikan komponen berhasil dibuat
  });

  it(`should have the title 'fe'`, () => {
    expect(app.title).toEqual('fe'); // Memastikan title adalah 'fe'
  });

  it('should render router-outlet', () => {
    const compiled = fixture.nativeElement as HTMLElement;
    expect(compiled.querySelector('router-outlet')).toBeTruthy();  // Memastikan router-outlet ada di template
  });
});
