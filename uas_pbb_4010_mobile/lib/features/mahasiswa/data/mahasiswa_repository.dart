import 'package:dio/dio.dart';
import 'package:cross_file/cross_file.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/network/dio_client.dart';
import 'mahasiswa_model.dart';

class MahasiswaRepository {
  final DioClient _dioClient = DioClient();

  Future<List<Mahasiswa>> getMahasiswaList() async {
    final response = await _dioClient.dio.get(ApiConstants.mahasiswa);
    final List data = response.data['data'];
    return data.map((json) => Mahasiswa.fromJson(json)).toList();
  }

  Future<void> createMahasiswa({
    required String namaLengkap,
    required String nim,
    required String tanggalLahir,
    required String jenisKelamin,
    required String angkatan,
    required List<int> hobiIds,
    XFile? fotoProfil,
  }) async {
    MultipartFile? multipartFoto;
    if (fotoProfil != null) {
      final bytes = await fotoProfil.readAsBytes();
      multipartFoto = MultipartFile.fromBytes(bytes, filename: fotoProfil.name);
    }

    final formData = FormData.fromMap({
      'nama_lengkap': namaLengkap,
      'nim': nim,
      'tanggal_lahir': tanggalLahir,
      'jenis_kelamin': jenisKelamin,
      'angkatan': angkatan,
      'hobi_ids': hobiIds,
      if (multipartFoto != null) 'foto_profil': multipartFoto,
    });

    await _dioClient.dio.post(ApiConstants.mahasiswa, data: formData);
  }
}
