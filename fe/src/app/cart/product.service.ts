import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class ProductService {
    private apiUrl = 'http://localhost/mahiahijab/api/product/detailProductById.php';

    constructor(private http: HttpClient) { }

    // Mengambil detail produk berdasarkan ID
    getProductById(id: string): Observable<any> {
        return this.http.get<any>(`${this.apiUrl}?id=${id}`);
    }
}
