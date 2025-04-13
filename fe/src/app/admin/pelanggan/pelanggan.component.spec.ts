import { ComponentFixture, TestBed } from '@angular/core/testing';
import { CommonModule } from '@angular/common';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { of } from 'rxjs';
import { HttpClient } from '@angular/common/http';
import { AdminPelangganComponent } from './pelanggan.component';


describe('AdminPelangganComponent', () => {
  let component: AdminPelangganComponent;
  let fixture: ComponentFixture<AdminPelangganComponent>;
  let httpClientSpy: jasmine.SpyObj<HttpClient>;

  beforeEach(async () => {
    // Buat spy object dengan metode get, post, dan delete
    httpClientSpy = jasmine.createSpyObj('HttpClient', ['get', 'post', 'delete']);

    // Mock response untuk checkLogin (dipanggil saat ngOnInit)
    httpClientSpy.get.and.returnValue(of({ status: 'success', session_id: '123' }));

    await TestBed.configureTestingModule({
      imports: [CommonModule, HttpClientTestingModule, AdminPelangganComponent],
      providers: [{ provide: HttpClient, useValue: httpClientSpy }]
    }).compileComponents();

    fixture = TestBed.createComponent(AdminPelangganComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should fetch pelanggan data on init', () => {
    const mockData = { status: 'success', data: [{ id: '1', name: 'John Doe' }] };

    httpClientSpy.get.and.returnValue(of(mockData)); // override mock untuk getPelanggan

    component.getPelanggan();

    expect(component.pelangganList.length).toBe(1);
    expect(component.pelangganList[0].name).toBe('John Doe');
  });

  it('should delete pelanggan and refresh data', () => {
    const mockDeleteResponse = { status: 'success', message: 'Deleted' };
    httpClientSpy.post.and.returnValue(of(mockDeleteResponse));
    httpClientSpy.get.and.returnValue(of({ status: 'success', data: [] })); // refresh list

    component.deletePelanggan('1');

    expect(httpClientSpy.post).toHaveBeenCalled();
    expect(httpClientSpy.get).toHaveBeenCalled();
  });
});
