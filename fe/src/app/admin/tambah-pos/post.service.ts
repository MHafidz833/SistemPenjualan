import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class PosService {
    private apiUrl = 'http://localhost/mahiahijab/api/admin/pos/tambah-pos.php';
    private categoriesApiUrl = 'http://localhost/mahiahijab/api/admin/pos/kategori-pos.php';

    constructor(private http: HttpClient) { }

    // Ambil daftar kategori
    getCategories(): Observable<any> {
        return this.http.get<any>(`${this.categoriesApiUrl}`);
    }

    addPost(postData: any): Observable<any> {
        // Pastikan menggunakan headers JSON
        const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
        return this.http.post<any>(this.apiUrl, postData, { headers });
    }
}
