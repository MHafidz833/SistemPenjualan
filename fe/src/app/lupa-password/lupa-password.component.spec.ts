import { ComponentFixture, TestBed } from '@angular/core/testing';
import { LupaPasswordComponent } from './lupa-password.component';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { RouterTestingModule } from '@angular/router/testing';

describe('LupaPasswordComponent', () => {
  let component: LupaPasswordComponent;
  let fixture: ComponentFixture<LupaPasswordComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        LupaPasswordComponent,
        HttpClientTestingModule,
        RouterTestingModule
      ]
    }).compileComponents();

    fixture = TestBed.createComponent(LupaPasswordComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
