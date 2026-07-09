import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'mahasiswa_provider.dart';
import 'tambah_mahasiswa_page.dart';
import 'edit_mahasiswa_page.dart';
import '../data/mahasiswa_repository.dart';
import '../../auth/presentation/profile_page.dart';

class MahasiswaListPage extends ConsumerWidget {
  const MahasiswaListPage({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final mahasiswaAsync = ref.watch(mahasiswaListProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Data Mahasiswa'),
        actions: [
          IconButton(
            icon: const Icon(Icons.person_outline),
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const ProfilePage()),
              );
            },
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => const TambahMahasiswaPage()),
          );
        },
        child: const Icon(Icons.add),
      ),
      body: mahasiswaAsync.when(
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (err, stack) => Center(
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Text('Gagal memuat data: $err'),
          ),
        ),
        data: (mahasiswaList) {
          if (mahasiswaList.isEmpty) {
            return const Center(child: Text('Belum ada data mahasiswa.'));
          }
          return ListView.builder(
            itemCount: mahasiswaList.length,
            itemBuilder: (context, index) {
              final mhs = mahasiswaList[index];
              return ListTile(
                leading: CircleAvatar(
                  backgroundImage: mhs.fotoProfil != null
                      ? NetworkImage(
                          'http://127.0.0.1:8000/storage/${mhs.fotoProfil}',
                        )
                      : null,
                  child: mhs.fotoProfil == null
                      ? const Icon(Icons.person)
                      : null,
                ),
                title: Text(mhs.namaLengkap),
                subtitle: Text(
                  'NIM: ${mhs.nim} • Angkatan: ${mhs.angkatan}\nHobi: ${mhs.hobis.map((h) => h.namaHobi).join(', ')}',
                ),
                isThreeLine: true,
                trailing: PopupMenuButton<String>(
                  onSelected: (value) async {
                    if (value == 'edit') {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => EditMahasiswaPage(mahasiswa: mhs),
                        ),
                      );
                    } else if (value == 'hapus') {
                      final confirm = await showDialog<bool>(
                        context: context,
                        builder: (ctx) => AlertDialog(
                          title: const Text('Hapus Data'),
                          content: Text(
                            'Yakin ingin menghapus data ${mhs.namaLengkap}?',
                          ),
                          actions: [
                            TextButton(
                              onPressed: () => Navigator.pop(ctx, false),
                              child: const Text('Batal'),
                            ),
                            TextButton(
                              onPressed: () => Navigator.pop(ctx, true),
                              child: const Text(
                                'Hapus',
                                style: TextStyle(color: Colors.red),
                              ),
                            ),
                          ],
                        ),
                      );

                      if (confirm == true) {
                        try {
                          await MahasiswaRepository().deleteMahasiswa(mhs.id);
                          ref.invalidate(mahasiswaListProvider);
                          if (context.mounted) {
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                content: Text('Data berhasil dihapus.'),
                              ),
                            );
                          }
                        } catch (e) {
                          if (context.mounted) {
                            ScaffoldMessenger.of(context).showSnackBar(
                              SnackBar(content: Text('Gagal menghapus: $e')),
                            );
                          }
                        }
                      }
                    }
                  },
                  itemBuilder: (context) => [
                    const PopupMenuItem(value: 'edit', child: Text('Edit')),
                    const PopupMenuItem(value: 'hapus', child: Text('Hapus')),
                  ],
                ),
              );
            },
          );
        },
      ),
    );
  }
}
