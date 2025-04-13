import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root',
})
export class ProductService {
    private apiUrl = 'http://localhost/mahiahijab/api/product/rincianProduct.php';

    constructor(private http: HttpClient) { }

    getProductDetails(id: number): Observable<any> {
        return this.http.get<any>(`${this.apiUrl}?id=${id}`);
    }
}
