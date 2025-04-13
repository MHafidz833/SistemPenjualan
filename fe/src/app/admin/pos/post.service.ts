import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class PostService {
    private apiUrl = 'http://localhost/mahiahijab/api/admin/pos/pos.php';

    constructor(private http: HttpClient) { }

    getPosts(): Observable<any> {
        return this.http.get(this.apiUrl);
    }

    deletePost(id: number): Observable<any> {
        return this.http.delete(`${this.apiUrl}?id=${id}`);
    }

    
}
