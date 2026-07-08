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
}
