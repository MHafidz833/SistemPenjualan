import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class PosService {
    private baseUrl = 'http://localhost/mahiahijab/api/admin/pos';
    private categoriesApiUrl = `${this.baseUrl}/kategori-pos.php`;

    constructor(private http: HttpClient) { }

    getCategories(): Observable<any> {
        return this.http.get<any>(this.categoriesApiUrl);
    }

    updatePost(postData: any): Observable<any> {
        const headers = new HttpHeaders({ 'Content-Type': 'application/json' });

        return this.http.post<any>(`${this.baseUrl}/ubah-pos.php`, postData, { headers });
    }
}
