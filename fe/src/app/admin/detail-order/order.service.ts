import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class OrderService {
    private apiUrl = 'http://localhost/mahiahijab/api/admin/order/detail-order.php';

    constructor(private http: HttpClient) { }

    getOrderDetail(id: number): Observable<any> {
        return this.http.get<any>(`${this.apiUrl}?id=${id}`);
    }
}
