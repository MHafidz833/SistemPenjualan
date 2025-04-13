import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class CategoryService {
    private apiUrl = 'http://localhost/mahiahijab/api/admin/pos/kategori-pos.php';

    constructor(private http: HttpClient) { }

    getCategories(): Observable<any> {
        return this.http.get(this.apiUrl);
    }

    addCategory(name: string): Observable<any> {
        const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
        const body = JSON.stringify({ nama: name });

        return this.http.post(this.apiUrl, body, { headers });
    }

    deleteCategory(id: number): Observable<any> {
        return this.http.delete(`${this.apiUrl}?id=${id}`);
    }
}
