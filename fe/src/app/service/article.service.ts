import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class ArticleService {
    private apiUrl = 'http://localhost/mahiahijab/api/article/getArticleById.php';

    constructor(private http: HttpClient) { }

    getArticleById(id: string): Observable<any> {
        return this.http.get<any>(`${this.apiUrl}?id=${id}`);
    }
}
