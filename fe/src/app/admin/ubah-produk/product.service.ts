import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class PosService {
    private apiUrl = 'http://localhost/mahiahijab/api/admin/product/Product.php';

    constructor(private http: HttpClient) { }

    // Ambil semua produk
    getProducts(): Observable<any> {
        return this.http.get<any>(this.apiUrl);
    }

    // Ambil detail produk berdasarkan ID
    getProductById(id: number): Observable<any> {
        return this.http.get<any>(`${this.apiUrl}?id=${id}`);
    }

    // Tambah produk baru
    addProduct(productData: any): Observable<any> {
        const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
        return this.http.post<any>(this.apiUrl, productData, { headers });
    }

    // Update produk
    updateProduct(id: number, productData: any): Observable<any> {
        const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
        return this.http.put<any>(`${this.apiUrl}?id=${id}`, productData, { headers });
    }

    // Hapus produk
    deleteProduct(id: number): Observable<any> {
        return this.http.delete<any>(`${this.apiUrl}?id=${id}`);
    }
}
