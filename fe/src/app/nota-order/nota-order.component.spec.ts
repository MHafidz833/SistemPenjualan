import { ComponentFixture, TestBed } from '@angular/core/testing';
import { NotaOrderComponent } from './nota-order.component';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { RouterTestingModule } from '@angular/router/testing';

describe('NotaOrderComponent', () => {
  let component: NotaOrderComponent;
  let fixture: ComponentFixture<NotaOrderComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        NotaOrderComponent,
        HttpClientTestingModule,
        RouterTestingModule
      ]
    }).compileComponents();

    fixture = TestBed.createComponent(NotaOrderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
