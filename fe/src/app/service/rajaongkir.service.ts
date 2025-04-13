import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class RajaOngkirService {
    private baseUrl = 'http://localhost/mahiahijab/api/rajaongkir/api.php'; // API PHP lokal

    constructor(private http: HttpClient) { }

    // **Mendapatkan daftar provinsi**
    getProvinces(): Observable<any> {
        return this.http.get(`${this.baseUrl}?endpoint=provinces`);
    }

    // **Mendapatkan daftar semua kota**
    getAllCities(): Observable<any> {
        return this.http.get(`${this.baseUrl}?endpoint=cities`);
    }

    // **Mendapatkan daftar kota berdasarkan provinsi**
    getCities(provinceId: string | null): Observable<any> {
        const url = provinceId ? `${this.baseUrl}?endpoint=cities&province=${provinceId}` : `${this.baseUrl}?endpoint=cities`;
        return this.http.get(url);
    }

    // **Menghitung ongkir sesuai API PHP**
    calculateOngkir(origin: string, destination: string, weight: number, courier: string): Observable<any> {
        const url = `${this.baseUrl}?endpoint=ongkir&origin=${origin}&destination=${destination}&weight=${weight}&courier=${courier}`;
        return this.http.get(url);
    }

}
