import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class OrderService {
    private apiUrl = 'http://localhost/mahiahijab/api/order/order.php';

    constructor(private http: HttpClient) { }

    /**
     * Mengirim pesanan ke API dengan metode POST
     * @param orderData Data pesanan dalam format JSON
     * @returns Observable<Response>
     */
    placeOrder(orderData: any): Observable<any> {
        const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
        return this.http.post<any>(this.apiUrl, orderData, { headers });
    }
}
