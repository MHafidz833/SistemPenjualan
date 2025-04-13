import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root',
})
export class OrderService {
    private apiUrl = 'http://localhost/mahiahijab/api/order/notaOrder.php';

    constructor(private http: HttpClient) { }

    getOrderDetails(id: number): Observable<any> {
        const params = new HttpParams().set('id', id.toString());
        return this.http.get<any>(this.apiUrl, { params });
    }
}
