import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class UserserviceService {

  constructor(private http: HttpClient) { }
  private apiUrl = 'http://localhost/Angularform/index.php';
  private apiUrl2 = 'http://localhost/Angularform/form.php';

  form(create: any) {
    let hobbiesValue = create.hobbies.toString();
    console.log('Hobbies:', hobbiesValue);
    create.hobbies = hobbiesValue;

    let genderValue = create.gender.toString();
    console.log('Gender:', genderValue);
    create.gender = genderValue;

    const filePath = create.file;
    if (filePath) {
      const fileName = filePath.split(/[\\/]/).pop();
      console.log('File Name:', fileName);
      create.file = fileName;
      console.log('File Path:', filePath);
    }

    let data = new FormData();
    console.log('Form Data:', create);
    console.log('Type:', typeof create);

    data.append('Arrar', JSON.stringify(create));
    return this.http.post('http://localhost/Angularform/form.php?action=create', data);
  }

  getform(id: any) {
    return this.http.get<any>(`http://localhost/Angularform/form.php?action=getalldata&id=${id}`);


  }
  gettable() {
    return this.http.get(this.apiUrl)
  }

  update() {
    return this.http.get(this.apiUrl)
  }

  getdesignation() {
    return this.http.get<any>(`http://localhost/Angularform/form.php?action=getdesignation`);
  }

  updateForm(data: any) {
    const filePath = data.file;
    if (filePath) {
      const fileName = filePath.split(/[\\/]/).pop();
      console.log('File Name:', fileName);
      data.file = fileName;
    }
    let data1 = new FormData();
    data1.append('Arrar', JSON.stringify(data));

    return this.http.post(`http://localhost/Angularform/index.php`, data1);
  }

  delete(id: any) {
    return this.http.get(`http://localhost/Angularform/index.php?id=${id}`);
  }

}
