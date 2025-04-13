import { ComponentFixture, TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { ActivatedRoute } from '@angular/router';  // Impor ActivatedRoute
import { of } from 'rxjs';
import { AdminEditOrderComponent } from './edit-order.component';

describe('AdminEditOrderComponent', () => {
  let component: AdminEditOrderComponent;
  let fixture: ComponentFixture<AdminEditOrderComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminEditOrderComponent, HttpClientTestingModule],
      providers: [
        // Mock ActivatedRoute
        {
          provide: ActivatedRoute,
          useValue: {
            snapshot: {
              paramMap: {
                get: (key: string) => '123',  // Menyediakan ID order sebagai mock
              },
            },
          },
        },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(AdminEditOrderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
