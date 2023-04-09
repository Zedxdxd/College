#include "Olympiad_Olympiad.h"

#include <string>
#include <unordered_set>
#include <iostream>
#include <unordered_map>
#include <algorithm>

using namespace std;

vector<string> get_values_for_filters(string data) {
	vector<string> ret;
	string tmp = "";
	for (int i = 0; i < data.size(); i++) {
		if (data[i] == '!') {
			ret.push_back(tmp);
			tmp = "";
		}
		else {
			tmp += data[i];
		}
	}
	ret.push_back(tmp);
	return ret;
}

// filters.size must be 5, data >= 6
bool compare_data_and_filters(vector<string> data, vector<string> filters) {
	return	(filters[0] == "-1" || filters[0] == data[1]) &&
			(filters[1] == "-1" || filters[1] == data[2]) &&
			(filters[2] == "-1" || filters[2] == data[3]) &&
			(filters[3] == "-1" || filters[3] == data[4]) &&
			(filters[4] == "-1" || filters[4] == data[5]) &&
			(filters[5] == "-1" || filters[5] == data[6]);
}

// format for every string in data
// identificator!countryName!sportName!year!INDIVIDUAL / TEAM!wonMedal!Season
// identificator is either id of athlete or disciplineName
JNIEXPORT jint JNICALL Java_Olympiad_Olympiad_evaluateNumber
	(JNIEnv* env, jobject this_obj, jobjectArray filters, jobjectArray data) {
	
	unordered_set<string> filtered_data; // data that satisfies the filters 
	jsize filters_size = env->GetArrayLength(filters);
	jsize data_size = env->GetArrayLength(data);
	vector<string> vec_filters;
	for (jsize i = 0; i < filters_size; i++) {
		jobject obj_filter = env->GetObjectArrayElement(filters, i);
		jstring j_filter = (jstring)obj_filter;
		string filter = env->GetStringUTFChars(j_filter, nullptr);
		vec_filters.push_back(filter);
	}
	for (jsize i = 0; i < data_size; i++) {
		jobject obj_data = env->GetObjectArrayElement(data, i);
		jstring j_data = (jstring)obj_data;
		string cpp_data = env->GetStringUTFChars(j_data, nullptr);
		vector<string> separated_data = get_values_for_filters(cpp_data);
		if (compare_data_and_filters(separated_data, vec_filters)) {
			filtered_data.insert(separated_data[0]);
		}

	}
	return filtered_data.size();

}

// format for every string in data
// identificator!countryName!sportName!year!INDIVIDUAL / TEAM!wonMedal!Season!metric
// identificator is id of athlete
// metric is either height or weight
JNIEXPORT jdouble JNICALL Java_Olympiad_Olympiad_average
	(JNIEnv* env, jobject this_obj, jobjectArray filters, jobjectArray competitors) {
	
	unordered_set<string> filtered_athletes; // athletes that satisfy the filters 
	jsize filters_size = env->GetArrayLength(filters);
	jsize competitors_size = env->GetArrayLength(competitors);
	vector<string> vec_filters;
	for (jsize i = 0; i < filters_size; i++) {
		jobject obj_filter = env->GetObjectArrayElement(filters, i);
		jstring j_filter = (jstring)obj_filter;
		string filter = env->GetStringUTFChars(j_filter, nullptr);
		vec_filters.push_back(filter);
	}

	jdouble avg = 0;
	jsize count = 0;
	
	for (jsize i = 0; i < competitors_size; i++) {
		jobject obj_competitor = env->GetObjectArrayElement(competitors, i);
		jstring j_competitor = (jstring)obj_competitor;
		string cpp_competitor = env->GetStringUTFChars(j_competitor, nullptr);
		vector<string> separated_data = get_values_for_filters(cpp_competitor);
		if (compare_data_and_filters(separated_data, vec_filters)) {
			if (stod(separated_data[7]) != 0 && filtered_athletes.find(separated_data[0]) == filtered_athletes.end()) {
				count++;
				avg += stod(separated_data[7]);
				filtered_athletes.insert(separated_data[0]);
			}
		}

	}

	return count == 0 ? 0 : avg / count;
}


// format for every string in data
// identificator!countryName!sportName!year!INDIVIDUAL / TEAM!wonMedal!Season
// identificator is either id of athlete or disciplineName
JNIEXPORT jstring JNICALL Java_Olympiad_Olympiad_nativeNumberCompetitorsByCountry
(JNIEnv* env, jobject this_obj, jobjectArray filters, jobjectArray data) {

	unordered_map<string, unordered_set<string>> filtered_data;  // data that satisfies the filters, grouped by country

	jsize filters_size = env->GetArrayLength(filters);
	jsize data_size = env->GetArrayLength(data);
	vector<string> vec_filters;
	for (jsize i = 0; i < filters_size; i++) {
		jobject obj_filter = env->GetObjectArrayElement(filters, i);
		jstring j_filter = (jstring)obj_filter;
		string filter = env->GetStringUTFChars(j_filter, nullptr);
		vec_filters.push_back(filter);
	}
	for (jsize i = 0; i < data_size; i++) {
		jobject obj_data = env->GetObjectArrayElement(data, i);
		jstring j_data = (jstring)obj_data;
		string cpp_data = env->GetStringUTFChars(j_data, nullptr);
		vector<string> separated_data = get_values_for_filters(cpp_data);
		if (compare_data_and_filters(separated_data, vec_filters)) {
			// mozda treba if ?
			filtered_data[separated_data[1]].insert(separated_data[0]);
		
		}

	}

	vector<pair<string, int>> tmp_vec_for_sorting;
	for (auto& it : filtered_data) {
		tmp_vec_for_sorting.push_back({ it.first, it.second.size() });
	}

	sort(tmp_vec_for_sorting.begin(), tmp_vec_for_sorting.end(),
		[](const pair<string, int>& p1, const pair<string, int>& p2) {
			return p1.second > p2.second;
		});

	string result = "";
	for (auto& it : tmp_vec_for_sorting) {
		result += it.first + "$" + to_string(it.second) + "!";
	}
	
	// no sorting
	/*string result = "";
	for (auto& it : filtered_data) {
		result += it.first + "$" + to_string(it.second.size()) + "!";
	}*/
	return env->NewStringUTF(result.c_str());

}


// format for every string in data
// disciplineName!yearSeason
JNIEXPORT jstring JNICALL Java_Olympiad_Olympiad_nativeNumberDisciplinesByYearSeason
	(JNIEnv* env, jobject this_object, jobjectArray data) {

	unordered_map<string, unordered_set<string>> grouped_data;

	jsize data_size = env->GetArrayLength(data);
	for (jsize i = 0; i < data_size; i++) {
		jobject obj_data = env->GetObjectArrayElement(data, i);
		jstring j_data = (jstring)obj_data;
		string cpp_data = env->GetStringUTFChars(j_data, nullptr);
		vector<string> separated_data = get_values_for_filters(cpp_data);
		grouped_data[separated_data[1]].insert(separated_data[0]);
	}

	string result = "";
	for (auto& it : grouped_data) {
		result += it.first + "$" + to_string(it.second.size()) + "!";
	}
	return env->NewStringUTF(result.c_str());
}


// format for every string in data
// id!yearSeason!metric
JNIEXPORT jstring JNICALL Java_Olympiad_Olympiad_nativeAvgByYearSeason
	(JNIEnv* env, jobject this_object, jobjectArray data) {

	unordered_map<string, unordered_set<string>> grouped_data;
	unordered_map<string, double> athletes_metric; // maps id to metric for calculation

	jsize data_size = env->GetArrayLength(data);
	for (jsize i = 0; i < data_size; i++) {
		jobject obj_data = env->GetObjectArrayElement(data, i);
		jstring j_data = (jstring)obj_data;
		string cpp_data = env->GetStringUTFChars(j_data, nullptr);
		vector<string> separated_data = get_values_for_filters(cpp_data);
		grouped_data[separated_data[1]].insert(separated_data[0]);
		athletes_metric[separated_data[0]] = stod(separated_data[2]);
	}

	string result = "";
	for (auto& it : grouped_data) {
		double avg = 0;
		int cnt = 0;
		for (auto id : it.second) {
			if (athletes_metric[id] != 0) {
				avg += athletes_metric[id];
				cnt++;
			}
		}
		result += it.first + "$" + to_string(cnt == 0 ? 0 : avg / cnt) + "!";
	}
	return env->NewStringUTF(result.c_str());
}