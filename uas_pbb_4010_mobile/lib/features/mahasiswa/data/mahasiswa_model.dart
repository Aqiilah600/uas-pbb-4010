class Hobi {
  final int id;
  final String namaHobi;

  Hobi({required this.id, required this.namaHobi});

  factory Hobi.fromJson(Map<String, dynamic> json) {
    return Hobi(id: json['id'], namaHobi: json['nama_hobi']);
  }
}

class Mahasiswa {
  final int id;
  final String namaLengkap;
  final String nim;
  final String tanggalLahir;
  final String jenisKelamin;
  final String? fotoProfil;
  final String angkatan;
  final List<Hobi> hobis;

  Mahasiswa({
    required this.id,
    required this.namaLengkap,
    required this.nim,
    required this.tanggalLahir,
    required this.jenisKelamin,
    required this.fotoProfil,
    required this.angkatan,
    required this.hobis,
  });

  factory Mahasiswa.fromJson(Map<String, dynamic> json) {
    return Mahasiswa(
      id: json['id'],
      namaLengkap: json['nama_lengkap'],
      nim: json['nim'],
      tanggalLahir: json['tanggal_lahir'],
      jenisKelamin: json['jenis_kelamin'],
      fotoProfil: json['foto_profil'],
      angkatan: json['angkatan'],
      hobis: (json['hobis'] as List).map((h) => Hobi.fromJson(h)).toList(),
    );
  }
}
