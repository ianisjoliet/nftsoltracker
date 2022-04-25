import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  constructor(private http: HttpClient) { }

  postProduct(data: any) {
    return this.http.post<any>("http://localhost:300/productList", data);
  }

  getProduct() {
    return this.http.get<any>("https://mocki.io/v1/c129adca-337c-47c1-8d19-5886eda6f370");
  }
}
